/* eslint no-console: ["error", { allow: ["warn", "error"] }] */


//const aliases = require('@/../components_config/webpack.components.runtime.json');
//https://stackoverflow.com/questions/34944099/how-to-import-a-json-file-in-ecmascript-6/56668477
//in typescript or babel importing json can be done with
//https://hackernoon.com/import-json-into-typescript-8d465beded79
import * as aliases_json from '@/../components_config/webpack.components.runtime.json';
const aliases = aliases_json.default
//aliases = aliases.default
//const word = data.name;
//console.log(word); // output 'testing'
//console.log(data)
//alert(AAAA)

export default {
    name: 'BaseMixin',
    data() {
        return {}
    },
    mounted() {

    },
    methods: {

        /**
         * Returns an array of all templates up to the root one.
         * Good for showing debug info
         * @param string component
         * @return string[]
         */
        get_parent_component_names(component) {
            if (typeof component === 'undefined') {
                component = this
            }
            let arr = [];
            do {
                arr.push(component.$options.name);//the name of the template
                component = component.$parent;
            } while (typeof component.$parent !== 'undefined');
            arr = arr.reverse()
            return arr;
        },

        /**
         * Returns the parent component based on name (no matter how far it is)
         * Returns null if no parent controller with the given name is found
         * @param string parent_component_name
         * @return null|Vue
         */
        get_parent_component_by_name(parent_component_name, component) {
            if (typeof component === 'undefined') {
                component = this
            }
            let parent_component = null
            while (component.$parent) {
                if (component.$options.name === parent_component_name) {
                    parent_component = component
                    break;
                }
                component = component.$parent
            }
            return parent_component
        },
        
        /**
         * Accepts path that contains alias and returns resolved path.
         * If the path does not contain an alias the same string is returned
         * If the path contains an unknown alias an exception is thrown
         * @param string path_with_alias
         * @return string
         */
        resolve_alias(path_with_alias) {
            if (path_with_alias.indexOf('@') === -1) {
                return path_with_alias;
            }
            let resolved_path = '';
            let lookup_alias = path_with_alias.match(/(@.*?)\//)[1] // ? - ungreedy - must be used
            if (typeof aliases[lookup_alias] !== 'undefined') {
                resolved_path = path_with_alias.replace(lookup_alias, aliases[lookup_alias]);
            }
            //return lookup_alias
            // //this is wrong
            // // for (const alias in aliases) {
            // //     if (alias !== '@' && path_with_alias.indexOf(alias) !== -1) {
            // //         resolved_path = path_with_alias.replace(alias, aliases[alias]);
            // //         break;
            // //     }
            // // }
            if (!resolved_path) {
                let unknown_alias = path_with_alias.substring(path_with_alias.indexOf('@'), path_with_alias.indexOf('/'));
                throw new Error(`The provided path ${path_with_alias} contains an unknown alias ${unknown_alias}.`);
            }
            return resolved_path;
        },

        /**
         *
         * @returns {*}
         */
        get_aliases() {
            return aliases;
        }
        //perhaps try https://www.npmjs.com/package/current-script-polyfill
        // get_component_file(component) {
        //  //console.log(__filename)//produces index.js...
        //console.log(document.currentScript)
        //let scripts = document.getElementsByTagName('script');
        //console.log(scripts)
        // }
    }
}
