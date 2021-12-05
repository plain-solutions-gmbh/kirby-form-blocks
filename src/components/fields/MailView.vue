<template>
    <k-view class="k-field-type-mail-view">
        <k-items>
            <k-item 
                v-for="mail in list"
                :key="mail.id"
                class="k-field-type-mail-list-item"
                @click="openMail(mail)"
                @action="action"
                :options="[
                    mail.read == '' ? {icon: 'preview', text: $t('form.block.inbox.asread'), click: () => setRead(true, mail)} :
                    {icon: 'unread', text: $t('form.block.inbox.asunread'), click: () => setRead(false, mail)},
                    {icon: 'trash', text: $t('form.block.inbox.delete'), click: () => deleteMail(mail)}
                ]"
            >
                
                <k-status-icon :status="mail.status" :tooltip="mail.tooltip"/>

                <header class="k-item-content">
                    <slot>
                        <h3 class="k-item-title">{{mail.title}}</h3>
                        <p class="k-item-info" v-html="mail.desc" />
                    </slot>
                </header>
                
            </k-item>

            <k-item
            v-if="list.length == 0"
                class="k-field-type-page-list-item-empty"
                :text="$t('form.block.inbox.empty')"
                disabled="true"
            />
            
        </k-items>
        <k-text class="k-field-type-page-change-display">
            <a v-if="!displayShown" @click="displaySet(true)">{{$t('form.block.inbox.changedisplay')}}</a>
            <a v-if="displayShown" @click="displaySet(false)">{{$t('close')}}</a>
        </k-text>

        <div v-if="displayShown">
            <k-text-field :label="$t('form.block.inbox.display')" v-model="value" @input="$emit('input', $event)" />
        </div>

        <k-dialog ref="dialog" class="k-field-type-page-dialog" size="medium">
        
            <k-headline>{{current.title}}</k-headline>
            <div v-html="current.summary" class="k-field-type-page-dialog-table" />

            <k-fieldset v-model="current" v-if="current.length > 0" disabled="true" :fields="prev" />

            <k-info-field :text="current.error" v-if="current.error" theme="negative" />

            <template slot="footer">
                <k-button-group>
                <k-button v-if="current.read != ''" @click="setRead(false)">{{$t('form.block.inbox.asunread')}}</k-button>
                <k-button icon="times" @click="$refs.dialog.close()">{{$t('close')}}</k-button>
                <k-button v-if="current.read == ''" @click="setRead(true)">{{$t('form.block.inbox.asread')}}</k-button>
                </k-button-group>
            </template>
            
        </k-dialog>
    </k-view>

</template>

<script>
export default {
    created () {
        this.findId(this.$parent)
    },
    data () {
        return {
            new: [],
            read: [],
            data: [],
            current: [],
            displayShown: false,
            id: 0,
            parent:0
        }
    },
    props: {
        value: String
    },
    computed: {
        prev () {
            return this.previewfields
        },
        list () {
            let list = [];

            for (let [slug, req] of Object.entries(this.data)) {
                req.id = slug.split("/").at(-1);
                req.summary = JSON.parse(req.formdata).summary;
                req.status = this.getStatus(req);
                req.tooltip = this.getTooltip(req);
                let thisDate = this.$library.dayjs(req.received, 'YYYY-MM-DD HH:mm:ss')
                req.desc = thisDate.isValid() ? thisDate : "";
                req.title = this.getLabel(req);
                list.push(req);
            }

           return list;
        },
    },
    methods: {
        findId (parent) {
            if (!parent) {
                throw this.$t('form.block.inbox.notinblock');
            }
            if (this.parent = parent.$parent?.$options?.propsData?.id ?? false) {
                this.$api.get("form/get-requests", {form: this.parent})
                .then((data) => this.data = data)
                return;
            }
            this.findId(parent.$parent);
        },
        getLabel (req) {
            if (this.value == "") 
                return req.id

            return this.$helper.string.template(this.value, JSON.parse(req.formdata));
        },
        getStatus (req) {

            if (req.read)
                return "unlisted";

            if (req.error)
                return "draft";

            return "listed";

        },
        getTooltip (req) {

            if (req.error != "")
                return req.error;

            if (req.read != "")
                return this.$t("form.block.inbox.tooltip.read");

            return this.$t('form.block.inbox.tooltip.unread');

        },
        setRead (state, request = false) {

            if (!request) 
                request = this.current

            let params = {
                form: this.parent,
                request: request.id,
                state: state
            }

            this.$api.get("form/set-read", params).then((data) => {

                this.data = data;
                this.$refs.dialog.close();
                this.$events.$emit("form.update");
                
            })
        },
        openMail (request) {
            this.current= request;
            this.$refs.dialog.open();
        },
        deleteMail (request) {
            
            this.$api.get("form/delete-request", { form: this.parent, request: request.id }).then((data) => {

                this.data = data;
                
            })
        },
        displaySet (state) {
            this.displayShown = state;
            this.$events.$emit("form.update");
        }
    }
};
</script>

<style lang="scss">

    .k-field-type-mail-view {
        padding:0;
    }

    .k-field-type-mail-list-item-empty em {
        font-style: italic;
        color: var(--color-text-light)
    }

    .k-field-type-page-dialog h2.k-headline {

        padding-bottom:15px;
    }

    .k-field-type-page-change-display {
        text-align: right;
        > a{
            color: var(--color-focus);
            font-size: 0.8rem;
            padding-top: 10px;
            cursor: pointer;
        } 
    }

    .k-field-type-page-dialog-table {

        > table{

            width:100%;
            padding: 15px;
            background: var(--color-gray-100);

            td, th {
                line-height: 1.25em;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
        & + fieldset.k-fieldset {
            margin-top: 30px;

        }
    }


</style>