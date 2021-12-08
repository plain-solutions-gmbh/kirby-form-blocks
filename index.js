(function() {
  "use strict";
  var render$2 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("div", { staticClass: "k-block-type-form", on: { "click": _vm.open } }, [_c("div", { staticClass: "k-block-type-form-wrapper", attrs: { "data-state": _vm.state } }, [_c("k-input", { attrs: { "name": "name", "type": "text" }, on: { "input": _vm.onInput }, model: { value: _vm.content.name, callback: function($$v) {
      _vm.$set(_vm.content, "name", $$v);
    }, expression: "content.name" } }), _c("k-tag", { attrs: { "data-state": _vm.state } }, [_vm._v(_vm._s(_vm.$t("form.block.inbox.show")) + " (" + _vm._s(_vm.stateText) + ")")])], 1)]);
  };
  var staticRenderFns$2 = [];
  render$2._withStripped = true;
  var Form_vue_vue_type_style_index_0_lang = "";
  function normalizeComponent(scriptExports, render2, staticRenderFns2, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render2) {
      options.render = render2;
      options.staticRenderFns = staticRenderFns2;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(this, (options.functional ? this.parent : this).$root.$options.shadowRoot);
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const __vue2_script$2 = {
    data() {
      return {
        total: "-",
        new: "-",
        error: 0
      };
    },
    computed: {
      state() {
        if (this.error > 0)
          return "error";
        if (this.total == "-")
          return "wait";
        if (this.new > 0 && this.total > 0)
          return "new";
        return "ok";
      },
      stateText() {
        if (typeof this.error === "string")
          return this.error;
        if (this.total == "-")
          return this.$t("loading") + "...";
        let out = this.new + "/" + this.total + " " + this.$t("form.block.inbox.unread");
        if (this.error > 0)
          return out + " & " + this.error + " " + this.$t("form.block.inbox.failed");
        return out;
      }
    },
    destroyed() {
      this.$events.$off("form.update", this.updateCount);
    },
    created() {
      var _a, _b, _c, _d;
      const fields = (_d = (_c = (_b = (_a = this == null ? void 0 : this.fieldset) == null ? void 0 : _a.tabs) == null ? void 0 : _b.inbox) == null ? void 0 : _c.fields) != null ? _d : {};
      Object.keys(fields).forEach((mailview) => {
        fields[mailview].parent = this.$attrs.id;
      });
      this.updateCount();
      this.$events.$on("form.update", this.updateCount);
    },
    methods: {
      updateCount() {
        const $this = this;
        this.$api.get("form/get-requests-count", { form: this.$attrs.id }).then(function(data) {
          $this.total = data[0];
          $this.new = data[1];
          $this.error = data[2];
        }).catch(function() {
          $this.error = $this.$t("form.block.inbox.error");
        });
      },
      onInput(value) {
        this.$emit("update", value);
      }
    }
  };
  const __cssModules$2 = {};
  var __component__$2 = /* @__PURE__ */ normalizeComponent(__vue2_script$2, render$2, staticRenderFns$2, false, __vue2_injectStyles$2, null, null, null);
  function __vue2_injectStyles$2(context) {
    for (let o in __cssModules$2) {
      this[o] = __cssModules$2[o];
    }
  }
  __component__$2.options.__file = "src/components/blocks/Form.vue";
  var Form = /* @__PURE__ */ function() {
    return __component__$2.exports;
  }();
  var render$1 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-select-field", { attrs: { "value": _vm.value, "options": _vm.selectOptions, "required": _vm.required, "label": _vm.label, "help": _vm.help }, on: { "input": _vm.onInput } });
  };
  var staticRenderFns$1 = [];
  render$1._withStripped = true;
  const __vue2_script$1 = {
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
      }
    },
    data() {
      return {
        listItems: []
      };
    },
    computed: {
      selectOptions() {
        var _a;
        if (this.listItems)
          return (_a = this.listItems) == null ? void 0 : _a.map((a) => {
            var _a2, _b;
            return {
              text: (_a2 = a == null ? void 0 : a.label) != null ? _a2 : "error",
              value: (_b = a == null ? void 0 : a.slug) != null ? _b : "error"
            };
          });
        return [];
      }
    },
    created() {
      this.findOptions(this);
    },
    methods: {
      findOptions(parent) {
        var _a, _b;
        if (!parent) {
          throw this.$t("form.block.inbox.notinblock");
        }
        let val = (_b = (_a = parent == null ? void 0 : parent.value) == null ? void 0 : _a.options) != null ? _b : false;
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
  };
  const __cssModules$1 = {};
  var __component__$1 = /* @__PURE__ */ normalizeComponent(__vue2_script$1, render$1, staticRenderFns$1, false, __vue2_injectStyles$1, null, null, null);
  function __vue2_injectStyles$1(context) {
    for (let o in __cssModules$1) {
      this[o] = __cssModules$1[o];
    }
  }
  __component__$1.options.__file = "src/components/fields/SelectOption.vue";
  var SelectOption = /* @__PURE__ */ function() {
    return __component__$1.exports;
  }();
  var render = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("div", { staticClass: "k-field-type-mail-view" }, [_c("k-items", [_vm._l(_vm.list, function(mail) {
      return _c("k-item", { key: mail.id, staticClass: "k-field-type-mail-list-item", attrs: { "options": [
        mail.read == "" ? { icon: "preview", text: _vm.$t("form.block.inbox.asread"), click: function() {
          return _vm.setRead(true, mail);
        } } : { icon: "unread", text: _vm.$t("form.block.inbox.asunread"), click: function() {
          return _vm.setRead(false, mail);
        } },
        { icon: "trash", text: _vm.$t("form.block.inbox.delete"), click: function() {
          return _vm.deleteMail(mail);
        } }
      ] }, on: { "click": function($event) {
        return _vm.openMail(mail);
      } } }, [_c("k-status-icon", { attrs: { "status": mail.status, "tooltip": mail.tooltip } }), _c("header", { staticClass: "k-item-content" }, [_vm._t("default", function() {
        return [_c("h3", { staticClass: "k-item-title" }, [_vm._v(_vm._s(mail.title))]), _c("p", { staticClass: "k-item-info", domProps: { "innerHTML": _vm._s(mail.desc) } })];
      })], 2)], 1);
    }), _vm.list.length == 0 ? _c("k-item", { staticClass: "k-field-type-page-list-item-empty", attrs: { "text": _vm.$t("form.block.inbox.empty"), "disabled": "true" } }) : _vm._e()], 2), _c("k-text", { staticClass: "k-field-type-page-change-display" }, [!_vm.displayShown ? _c("a", { on: { "click": function($event) {
      return _vm.displaySet(true);
    } } }, [_vm._v(_vm._s(_vm.$t("form.block.inbox.changedisplay")))]) : _vm._e(), _vm.displayShown ? _c("a", { on: { "click": function($event) {
      return _vm.displaySet(false);
    } } }, [_vm._v(_vm._s(_vm.$t("close")))]) : _vm._e()]), _vm.displayShown ? _c("div", [_c("k-text-field", { attrs: { "value": _vm.value, "label": _vm.$t("form.block.inbox.display") }, on: { "input": function($event) {
      return _vm.$emit("input", $event);
    } } })], 1) : _vm._e(), _c("k-dialog", { ref: "dialog", staticClass: "k-field-type-page-dialog", attrs: { "size": "medium" } }, [_c("k-headline", [_vm._v(_vm._s(_vm.current.title))]), _c("div", { staticClass: "k-field-type-page-dialog-table", domProps: { "innerHTML": _vm._s(_vm.current.summary) } }), _vm.current.length > 0 ? _c("k-fieldset", { attrs: { "disabled": "true", "fields": _vm.prev }, model: { value: _vm.current, callback: function($$v) {
      _vm.current = $$v;
    }, expression: "current" } }) : _vm._e(), _vm.current.error ? _c("k-info-field", { attrs: { "text": _vm.current.error, "theme": "negative" } }) : _vm._e(), _c("template", { slot: "footer" }, [_c("k-button-group", [_vm.current.read != "" ? _c("k-button", { on: { "click": function($event) {
      return _vm.setRead(false);
    } } }, [_vm._v(_vm._s(_vm.$t("form.block.inbox.asunread")))]) : _vm._e(), _c("k-button", { attrs: { "icon": "times" }, on: { "click": function($event) {
      return _vm.$refs.dialog.close();
    } } }, [_vm._v(_vm._s(_vm.$t("close")))]), _vm.current.read == "" ? _c("k-button", { on: { "click": function($event) {
      return _vm.setRead(true);
    } } }, [_vm._v(_vm._s(_vm.$t("form.block.inbox.asread")))]) : _vm._e()], 1)], 1)], 2)], 1);
  };
  var staticRenderFns = [];
  render._withStripped = true;
  var MailView_vue_vue_type_style_index_0_lang = "";
  const __vue2_script = {
    props: {
      value: {
        type: [String],
        default: ""
      },
      dateformat: {
        type: String,
        default: "DD.MM.YYYY HH:mm"
      }
    },
    data() {
      return {
        new: [],
        read: [],
        data: [],
        current: [],
        displayShown: false,
        id: 0,
        parent: 0
      };
    },
    computed: {
      prev() {
        return this.previewfields;
      },
      list() {
        let list = [];
        for (let [slug, req] of Object.entries(this.data)) {
          req.id = slug.split("/").at(-1);
          req.summary = JSON.parse(req.formdata).summary;
          req.status = this.getStatus(req);
          req.tooltip = this.getTooltip(req);
          let thisDate = this.$library.dayjs(req.received, "YYYY-MM-DD HH:mm:ss");
          req.desc = thisDate.isValid() ? thisDate.format(this.dateformat) : "";
          req.title = this.getLabel(req);
          list.push(req);
        }
        return list;
      }
    },
    created() {
      this.findId(this.$parent);
    },
    methods: {
      findId(parent) {
        var _a, _b, _c, _d;
        if (!parent) {
          throw this.$t("form.block.inbox.notinblock");
        }
        this.parent = (_d = (_c = (_b = (_a = parent.$parent) == null ? void 0 : _a.$options) == null ? void 0 : _b.propsData) == null ? void 0 : _c.id) != null ? _d : false;
        if (this.parent) {
          this.$api.get("form/get-requests", { form: this.parent }).then((data) => this.data = data);
          return;
        }
        this.findId(parent.$parent);
      },
      getLabel(req) {
        if (this.value == "")
          return req.id;
        return this.$helper.string.template(this.value, JSON.parse(req.formdata));
      },
      getStatus(req) {
        if (req.read)
          return "unlisted";
        if (req.error)
          return "draft";
        return "listed";
      },
      getTooltip(req) {
        if (req.error != "")
          return req.error;
        if (req.read != "")
          return this.$t("form.block.inbox.tooltip.read");
        return this.$t("form.block.inbox.tooltip.unread");
      },
      setRead(state, request = false) {
        if (!request)
          request = this.current;
        let params = {
          form: this.parent,
          request: request.id,
          state
        };
        this.$api.get("form/set-read", params).then((data) => {
          this.data = data;
          this.$refs.dialog.close();
          this.$events.$emit("form.update");
        });
      },
      openMail(request) {
        this.current = request;
        this.$refs.dialog.open();
      },
      deleteMail(request) {
        this.$api.get("form/delete-request", { form: this.parent, request: request.id }).then((data) => {
          this.data = data;
        });
      },
      displaySet(state) {
        this.displayShown = state;
        this.$events.$emit("form.update");
      }
    }
  };
  const __cssModules = {};
  var __component__ = /* @__PURE__ */ normalizeComponent(__vue2_script, render, staticRenderFns, false, __vue2_injectStyles, null, null, null);
  function __vue2_injectStyles(context) {
    for (let o in __cssModules) {
      this[o] = __cssModules[o];
    }
  }
  __component__.options.__file = "src/components/fields/MailView.vue";
  var MailView = /* @__PURE__ */ function() {
    return __component__.exports;
  }();
  window.panel.plugin("microman/form-blocks", {
    fields: {
      mailview: MailView,
      selectoption: SelectOption
    },
    blocks: {
      form: Form
    },
    icons: {
      form: '<path d="M6.9,13.6H2.2c-0.6,0-1.1-0.5-1.1-1.1V3.1C1.1,2.5,1.6,2,2.2,2h8.4c0.6,0,1.1,0.5,1.1,1.1v5.8 c0,0.3,0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5V3.1c0-1.2-0.9-2.1-2.1-2.1H2.2C1,1,0.1,1.9,0.1,3.1v9.5c0,1.2,0.9,2.1,2.1,2.1h4.7 c0.3,0,0.5-0.2,0.5-0.5C7.5,13.8,7.2,13.6,6.9,13.6z M9,4.1H3.8c-0.3,0-0.5,0.2-0.5,0.5c0,0.3,0.2,0.5,0.5,0.5H9 c0.3,0,0.5-0.2,0.5-0.5C9.6,4.4,9.3,4.1,9,4.1z M9.6,7.8c0-0.3-0.2-0.5-0.5-0.5H3.8c-0.3,0-0.5,0.2-0.5,0.5c0,0.3,0.2,0.5,0.5,0.5H9 C9.3,8.3,9.6,8.1,9.6,7.8z M3.8,10.4c-0.3,0-0.5,0.2-0.5,0.5c0,0.3,0.2,0.5,0.5,0.5h2.1c0.3,0,0.5-0.2,0.5-0.5 c0-0.3-0.2-0.5-0.5-0.5H3.8z M15.8,9.5c-0.2-0.2-0.5-0.2-0.7,0l-3.9,3.9l-1.8-1.8c-0.2-0.2-0.5-0.2-0.7,0c-0.2,0.2-0.2,0.5,0,0.7 l2,2c0,0.1,0.1,0.1,0.1,0.1c0.2,0.2,0.5,0.2,0.7,0l4.3-4.3C16,10,16,9.7,15.8,9.5z"/>',
      send: '<path d="M15.8,0.7C15.8,0.7,15.8,0.7,15.8,0.7C15.8,0.7,15.8,0.6,15.8,0.7c0-0.1,0-0.1,0-0.1c0,0,0-0.1,0-0.1c0,0,0,0,0,0 c0,0,0,0,0-0.1c0,0,0,0,0,0c0,0,0-0.1-0.1-0.1c0,0,0,0-0.1-0.1c0,0,0,0,0,0c0,0,0,0-0.1,0c0,0,0,0,0,0c0,0-0.1,0-0.1,0c0,0,0,0,0,0 c0,0,0,0-0.1,0c0,0,0,0,0,0c0,0,0,0-0.1,0c0,0,0,0,0,0c0,0-0.1,0-0.1,0L0.5,5.7C0.3,5.8,0.2,5.9,0.2,6.2c0,0.2,0.1,0.4,0.3,0.5 l6,2.9l2.9,6c0.1,0.2,0.3,0.3,0.5,0.3c0,0,0,0,0,0c0.2,0,0.4-0.1,0.5-0.3l5.5-14.6C15.8,0.8,15.8,0.8,15.8,0.7 C15.8,0.8,15.8,0.8,15.8,0.7C15.8,0.8,15.8,0.7,15.8,0.7z M13.2,2L6.7,8.5L2,6.2L13.2,2z M9.8,14L7.5,9.3L14,2.8L9.8,14z"/>',
      unread: '<path d="M15.72,8.44c0-0.26-0.11-0.52-0.3-0.71c-0.37-0.37-1.04-0.37-1.41,0c-0.08,0.08-0.13,0.18-0.18,0.28L13.82,8 c-0.78,1.17-2.98,4-5.82,4c-2.83,0-5.02-2.82-5.81-3.99c-0.01,0-0.01,0-0.02,0c-0.05-0.1-0.11-0.2-0.19-0.28 c-0.37-0.37-1.04-0.37-1.41,0C0.38,7.92,0.28,8.18,0.28,8.44c0,0.27,0.1,0.52,0.29,0.71c0,0.01-0.01,0.01-0.01,0.02 C1.55,10.64,4.23,14,8,14c3.92,0,6.68-3.66,7.56-5.01l-0.02-0.01C15.65,8.82,15.72,8.64,15.72,8.44z"/>'
    }
  });
})();
