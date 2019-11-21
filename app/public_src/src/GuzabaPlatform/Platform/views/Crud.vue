<template>
  	<div class="crud">
    	<h1>CRUD operations</h1>
    
    	<div class="content">

	    	<div id="sidebar" class="tab">
		        <h3>Classes</h3>
		        <ul>
		            <li v-for="(className, index) in classes" @click="getClassObjects(className)"> {{ className }}  </li>
		        </ul>
		    </div>

		    <div id="data" class="tab">
		    	<h3 v-if="selectedClassNameShort!=''">{{selectedClassNameShort}} <b-button variant="success" @click="showModal('post', newObject)" size="sm">Create New</b-button> </h3>

		    	<template v-if="!selectedClassName">
		    		<p>No class selected!</p>
		    	</template>

		    	<template v-else>
					<b-table striped show-empty :items="items" :fields="fields" empty-text="No records found!" @row-clicked="rowClickHandler"  no-local-sorting @sort-changed="sortingChanged" head-variant="dark" table-hover>

					  	<template slot="top-row" slot-scope="{ fields }">
					    	<td v-for="field in fields">
					      		<template v-if="field.key=='object_uuid'">
					      			<b-button size="sm" variant="outline-primary" type="submit" @click="search()">Search</b-button>
					      		</template>

					      		<template v-else>
					      			<b-form-input v-model="searchValues[field.key]" type="search" :placeholder="field.label"></b-form-input>
					      		</template>
					    	</td>
				  		</template>

						<template v-slot:cell(object_uuid)="row">  	
						  	<b-button size="sm" variant="outline-danger" v-on:click.stop="" @click="showModal('delete', row.item)">Delete</b-button>
						</template>

					</b-table>
				</template>

		        <b-pagination v-if="totalItems > limit" size="md" :total-rows="totalItems" v-model="currentPage" :per-page="limit"  align="center"></b-pagination>

			    <b-modal
			      id="crud-modal"
			      :title="modalTitle"
			      :header-bg-variant="modalVariant"
			      header-text-variant="light"
			      body-bg-variant="light"
			      body-text-variant="dark"
			      :ok-title="ButtonTitle"
			      :ok-variant="ButtonVariant"
			      centered
			      @ok="proceedAction($event)"
			      :cancel-disabled="actionState" 
			      :ok-disabled="loadingState" 
			      :ok-only="actionState && !loadingState"
			      size="lg"
			    >
				    <template v-if="!actionState">
				      	<p>{{actionTitle}}</p>

			      		<b-form-group class="form-group" v-for="(value, index) in putValues" v-if="index!='object_uuid'" :label="index" label-align="right" label-cols="3">

	      					<template  v-if="action=='delete'">
			      				<b-form-input :value="value" disabled></b-form-input>
			      			</template>
			      			
			      			<template v-else>
					      		<b-form-input v-model="putValues[index]"></b-form-input>
		    	  			</template>

		    	  		</b-form-group>
				    </template>

				    <template v-else>
				      	<p v-if="loadingState">
				        	{{loadingMessage}} 
				        	...
				     	</p>
				      	<p v-else>
					      	<template v-if="requestError == ''">
					      		{{successfulMessage}}
				            </template>
				            <template v-else>
				            	The operation can not be performed due to an error:<br />
				            	{{requestError}}
				            </template>
				      	</p>
				    </template>
	    		</b-modal>
	  		</div>
	    </div>
	</div>

</template>

<script>
import Hook from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
import { stringify } from 'qs'
export default {
    name: "Crud",
    components: {
        Hook
    },
    data() {
        return {
            classes: [],
            limit: 10,
            currentPage: 1,
            totalItems: 0,

            selectedClassName: '',
            selectedClassNameShort: '',
            sortBy: 'none',
	    	sortDesc: false,

            searchValues: {},
            putValues: {},

            requestError: '',

            action: '',
            actionTitle: '',
            modalTitle: '',
            modalVariant: '',
            ButtonTitle: '',
            ButtonVariant: '',

           	crudObjectUuid: '',

            actionState: false,
      		loadingState: false,

            loadingMessage: '',
      		successfulMessage: '',

      		items: [],
      		fields: [],

      		newObject: {}
        }
    },
    mounted() {
        var self = this;
        this.$http.get('/crud-classes')
            .then(resp => {
                self.classes = resp.data.classes;
            })
            .catch(err => {
                console.log(err);
            })
            .finally(function(){
            	self.resetParams('');
        		self.searchValues = {};
            });
    },
    methods: {

        getClassObjects(className) {

            className = className.split('\\').join('.');

            if (this.selectedClassName != className) {
            	this.resetParams(className);
        		this.searchValues = {};
            }

            for (var key in this.searchValues) {
            	if (this.searchValues[key] == '') {
            		delete this.searchValues[key];
            	}
            }

			let objJsonStr = JSON.stringify(this.searchValues);
			var searchValuesToPass = encodeURIComponent(window.btoa(objJsonStr));

            var self = this;

            this.$http.get('/crud-objects/' + this.selectedClassName + '/' + self.currentPage + '/' + self.limit + '/'+ searchValuesToPass + '/' + this.sortBy + '/' + this.sortDesc)
            .then(resp => {

	            this.fields = [];
	            this.newObject = {};

                for (var i in resp.data.properties) {
                	var key = resp.data.properties[i];

                    if (key != 'object_uuid') {
                    	self.fields.push({
                			key: key,
                			sortable: true
                    	});

            			self.newObject[key] = '';
                    } else {
                    	self.fields.push({
                			key: key,
                			label: 'Action',
                			sortable: false
                    	});
                    }
                }

                self.items = resp.data.data;

                self.totalItems = resp.data.totalItems;
            })
            .catch(err => {
                console.log(err);
            });
        },

        search() {
        	this.resetParams(this.selectedClassName);
        	this.getClassObjects(this.selectedClassName);
        },

        resetParams(className){   
        	this.currentPage = 1;
        	this.totalItems = 0;
        	this.selectedClassName = className;
        	this.selectedClassNameShort = className.split(".").pop();
        	this.sortBy = 'none';
        }, 

        rowClickHandler(record, index) {
        	this.showModal('put', record);
        },

        showModal(action, row) {
        	this.action = action;
        	this.crudObjectUuid = null;
        	this.putValues = row;

			for (var key in row) {
				if (key == "object_uuid") {
		    		this.crudObjectUuid = row[key];
				}
		    }

		    switch (this.action) {
		    	case 'delete' : 
            		this.modalTitle = 'Deleting object';
            		this.modalVariant = 'danger';
	            	this.ButtonVariant = 'danger';
	            	this.actionTitle = 'Are you sure, you want to delete object:';
	            	this.ButtonTitle = 'Delete';
		    	break;

		    	case 'put' :
            		this.modalTitle = 'Edit object';
            		this.modalVariant = 'success';
	            	this.ButtonVariant = 'success';
	            	this.actionTitle = this.selectedClassNameShort + ":";
	            	this.ButtonTitle = 'Save';
	            break;

		    	case 'post' :
            		this.modalTitle = 'Create new object';
            		this.modalVariant = 'success';
	            	this.ButtonVariant = 'success';
	            	this.actionTitle = this.selectedClassNameShort + ":";
	            	this.ButtonTitle = 'Save';
	            break;
		    }

            if (!this.crudObjectUuid && this.action != "post") {
                this.requestError = "This object has no meta data!";
    			this.actionState = true
	      		this.loadingState = false
	            this.ButtonTitle = 'Ok';
            } else {
    			this.actionState = false
	      		this.loadingState = false
            }

        	this.$bvModal.show('crud-modal');

        },

	    proceedAction(bvEvt) {
	    	if(!this.actionState) {
	        	bvEvt.preventDefault() //if actionState is false, doesn't close the modal
	        	this.actionState = true
	        	this.loadingState = true

			    var self = this;
			    var sendValues = {};

	            switch(this.action) {	            	
			    	case 'delete' :
			    		self.loadingMessage = 'Deleting object with uuid: ' + this.crudObjectUuid;
			    		var url = this.selectedClassNameShort.toLowerCase() + '/' + this.crudObjectUuid;
			    	break;

			    	case 'put' :			    		
			    		self.loadingMessage = 'Saving object with uuid: ' + this.crudObjectUuid;
			    		var url = this.selectedClassNameShort.toLowerCase() + '/' + this.crudObjectUuid;

			    		sendValues = this.putValues;
			    		delete sendValues['object_uuid'];
		            break;

			    	case 'post' :			    		
			    		self.loadingMessage = 'Saving object with uuid: ' + this.crudObjectUuid;
			    		var url = this.selectedClassNameShort.toLowerCase();
			    		
			    		sendValues = this.putValues;
			    		delete sendValues['object_uuid'];
		            break;
	            }

			    this.$http({
			    	method: this.action, 
			    	url: url, 
			    	data: this.$stringify(sendValues)
			    })
	            .then(resp => {
	            	self.requestError = '';
	            	self.successfulMessage = resp.data.message;
	            	self.getClassObjects(self.selectedClassName)
	            })
	            .catch(err => {
	                console.log(err);
	                self.requestError = err;
	            })
	            .finally(function(){
	          		self.loadingState = false
	          		self.actionState = true
		            self.ButtonTitle = 'OK';
		            self.ButtonVariant = 'success';
	            });

	      	}
	    },

	    sortingChanged(ctx) {
	    	this.sortBy = ctx.sortBy;
	    	this.sortDesc = ctx.sortDesc ? 1 : 0;

	    	this.getClassObjects(this.selectedClassName);
	    }
    },
	watch: {
	    currentPage: {
	      	handler: function(value) {
	        	this.getClassObjects(this.selectedClassName);
	      	}
	    }
	}
};

</script>

<style>
.content {
  height: 100vh;
  top: 64px;	
}

.tab {
  float: left;
  height: 100%;
  overflow: none;
  padding: 20px;
}

#sidebar{
  font-size: 10pt;
  border-width: 0 5px 0 0;
  border-style: solid;
  width: 30%;
  text-align: left;
}

#data {
  width: 65%;
  font-size: 10pt;
}

li {
  cursor: pointer;
}

.btn {
  width: 100%;
}

tr:hover{
  background-color: #ddd !important;
}

th:hover{
  background-color: #000 !important;
}

tr {
  cursor: pointer;
}
</style>
