/* eslint no-console: ["error", { allow: ["warn", "error"] }] */
//const aliases = require('@/../components_config/webpack.components.runtime.json')
import aliases from '@/../components_config/webpack.components.runtime.json'
export default {
    name: 'AliasesMixin',
    data() {
        return {
            aliases : []
        }
    },
    mounted() {
        this.aliases = aliases;
    },
    methods: {
        resolve_alias(alias) {
            let ret = ''
            for (const alias_key in this.aliases) {
                if (alias_key !='@' && alias.indexOf(alias_key) != -1) {
                    ret = this.aliases[alias_key];
                    break;
                }
            }
            return ret;
        }
    }
}
