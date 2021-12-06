<template>
<div @click="open" class="k-block-type-form">
<div class="k-block-type-form-wrapper" :data-state="state">
    <k-input v-model="content.name" name="name" type="text" @input="onInput"/>
    <k-tag :data-state="state">{{$t('form.block.inbox.show')}} ({{this.stateText}})</k-tag>
</div>
</div>
</template>

<script>

export default {
  data () {
    return {
      total: '-',
      new: '-',
      error: 0
    }
  },
  created () {
    //Debug!
    //setTimeout(this.open, 500)
    
    const fields = this?.fieldset?.tabs?.inbox?.fields ?? {};
    
    Object.keys(fields).forEach(mailview => {
      fields[mailview].parent = this.$attrs.id
    });

    this.updateCount()
    
    this.$events.$on("form.update", this.updateCount);
  },
  destroyed() {
    this.$events.$off("form.update", this.updateCount);
  },
  computed: {
    state () {

      if (this.error > 0)
        return "error";

      if (this.total == "-")
        return "wait";

      if (this.new > 0 && this.total > 0)
        return "new";
        
      return "ok";
    },
    stateText () {
      if (typeof this.error === 'string')
        return this.error;

      if (this.total == "-")
        return this.$t('loading')+"...";
      
      let out = this.new+"/"+this.total+" "+this.$t('form.block.inbox.unread');
      if (this.error > 0)
        return out+" & "+this.error+" "+this.$t('form.block.inbox.failed');

      return out
    }
  },
  methods: {
    updateCount() {
      const $this = this;

     this.$api.get("form/get-requests-count", {form: this.$attrs.id})
      .then(function (data) {
        $this.total = data[0];
        $this.new = data[1];
        $this.error = data[2];
      })
      .catch(function () {
        $this.error = $this.$t('form.block.inbox.error');
      })
    },
    onInput (value) {
      
        this.$emit("update", value);
      //$emit('input', $event)
    }
  }
  
};
</script>


<style lang="scss">

  .k-block-type-form {

    .k-block-type-form-wrapper, .k-input, .k-tag {
      display: inline-flex;
      width: auto;
    }

    .k-tag {
      color:--color-white !important;
      background-color: var(--color-gray-300);
      &[data-state=new] {
        background-color: var(--color-orange);
      }
      &[data-state=ok] {
        background-color: var(--color-green);
      }
      &[data-state=error] {
        background-color: var(--color-red);
      }
    }

    .k-block-type-form-wrapper {
      border: 1px solid var(--color-gray-300);

      &[data-state=new] {
        border-color: var(--color-orange);
      }
      &[data-state=ok] {
        border-color: var(--color-green);
      }
      &[data-state=error] {
        border-color: var(--color-red);
      }
    }

    .k-text-input {
      padding-left:  0.3rem;
    }
    
  }

</style>