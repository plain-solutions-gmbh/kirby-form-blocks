<template>

	<k-select-field
		:value="value"
		:options="selectOptions"
		:required="required"
		:label="label"
		:help="help"
		@input="onInput"
	/>

</template>
<script>
export default {
	props: {
		help: {
			type: String,
			default: ""
		},
		label: {
			type: String,
			default: ""
		},
		required: {
			type: Boolean,
			default: false
		},
		value: {
			type: String,
			default: ""
		},
	},
	data () {
		return {
			listItems: []
		}
	},
	computed: {
		selectOptions () {
			if (this.listItems)
				return this.listItems?.map(a => {
					return {
						text: a?.label ?? "error",
						value: a?.slug ?? "error"
					}
				})
			return [];
		}
	},
 	created () {
		 this.findOptions(this)
	},
	methods: {
		findOptions (parent) {
            if (!parent) {
                throw this.$t('form.block.inbox.notinblock');
            }

            let val = parent?.value?.options ?? false

            if (val) {
			   this.listItems = val;
            	return;
            }
            this.findOptions(parent.$parent);
		},
		onInput(value) {
			this.$emit("input", value);
		}
	}
}
</script>