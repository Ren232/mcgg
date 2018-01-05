/**
 * @return {undefined}
 */
function _init() {
  $.NSdash.layout = {
    /**
     * @return {undefined}
     */
    activate : function() {
      var domEvent = this;
      domEvent.fix();
      domEvent.fixSidebar();
      $(window, ".wrapper").resize(function() {
        domEvent.fix();
        domEvent.fixSidebar();
      });
    },
    /**
     * @return {undefined}
     */
    fix : function() {
      var headerHeight = $(".main-header").outerHeight() + $(".main-footer").outerHeight();
      var top = $(window).height();
      var c = $(".sidebar").height();
      if ($("body").hasClass("fixed")) {
        $(".content-wrapper, .right-side").css("min-height", top - $(".main-footer").outerHeight());
      } else {
        var h;
        if (top >= c) {
          $(".content-wrapper, .right-side").css("min-height", top - headerHeight);
          /** @type {number} */
          h = top - headerHeight;
        } else {
          $(".content-wrapper, .right-side").css("min-height", c);
          h = c;
        }
        var $doc = $($.NSdash.options.controlSidebarOptions.selector);
        if ("undefined" != typeof $doc) {
          if ($doc.height() > h) {
            $(".content-wrapper, .right-side").css("min-height", $doc.height());
          }
        }
      }
    },
    /**
     * @return {?}
     */
    fixSidebar : function() {
      return $("body").hasClass("fixed") ? ("undefined" == typeof $.fn.slimScroll && (window.console && window.console.error("Error: the fixed layout requires the slimscroll plugin!")), void($.NSdash.options.sidebarSlimScroll && ("undefined" != typeof $.fn.slimScroll && ($(".sidebar").slimScroll({
        destroy : true
      }).height("auto"), $(".sidebar").slimscroll({
        height : $(window).height() - $(".main-header").height() + "px",
        color : "rgba(0,0,0,0.2)",
        size : "3px"
      }))))) : void("undefined" != typeof $.fn.slimScroll && $(".sidebar").slimScroll({
        destroy : true
      }).height("auto"));
    }
  };
  $.NSdash.pushMenu = {
    /**
     * @param {?} container
     * @return {undefined}
     */
    activate : function(container) {
      var screenSizes = $.NSdash.options.screenSizes;
      $(container).on("click", function(types) {
        types.preventDefault();
        if ($(window).width() > screenSizes.sm - 1) {
          if ($("body").hasClass("sidebar-collapse")) {
            $("body").removeClass("sidebar-collapse").trigger("expanded.pushMenu");
          } else {
            $("body").addClass("sidebar-collapse").trigger("collapsed.pushMenu");
          }
        } else {
          if ($("body").hasClass("sidebar-open")) {
            $("body").removeClass("sidebar-open").removeClass("sidebar-collapse").trigger("collapsed.pushMenu");
          } else {
            $("body").addClass("sidebar-open").trigger("expanded.pushMenu");
          }
        }
      });
      $(".content-wrapper").click(function() {
        if ($(window).width() <= screenSizes.sm - 1) {
          if ($("body").hasClass("sidebar-open")) {
            $("body").removeClass("sidebar-open");
          }
        }
      });
      if ($.NSdash.options.sidebarExpandOnHover || $("body").hasClass("fixed") && $("body").hasClass("sidebar-mini")) {
        this.expandOnHover();
      }
    },
    /**
     * @return {undefined}
     */
    expandOnHover : function() {
      var node = this;
      /** @type {number} */
      var b = $.NSdash.options.screenSizes.sm - 1;
      $(".main-sidebar").hover(function() {
        if ($("body").hasClass("sidebar-mini")) {
          if ($("body").hasClass("sidebar-collapse")) {
            if ($(window).width() > b) {
              node.expand();
            }
          }
        }
      }, function() {
        if ($("body").hasClass("sidebar-mini")) {
          if ($("body").hasClass("sidebar-expanded-on-hover")) {
            if ($(window).width() > b) {
              node.collapse();
            }
          }
        }
      });
    },
    /**
     * @return {undefined}
     */
    expand : function() {
      $("body").removeClass("sidebar-collapse").addClass("sidebar-expanded-on-hover");
    },
    /**
     * @return {undefined}
     */
    collapse : function() {
      if ($("body").hasClass("sidebar-expanded-on-hover")) {
        $("body").removeClass("sidebar-expanded-on-hover").addClass("sidebar-collapse");
      }
    }
  };
  /**
   * @param {string} i
   * @return {undefined}
   */
  $.NSdash.tree = function(i) {
    var a = this;
    var animationSpeed = $.NSdash.options.animationSpeed;
    $(document).on("click", i + " li a", function(types) {
      var current = $(this);
      var el = current.next();
      if (el.is(".treeview-menu") && el.is(":visible")) {
        el.slideUp(animationSpeed, function() {
          el.removeClass("menu-open");
        });
        el.parent("li").removeClass("active");
      } else {
        if (el.is(".treeview-menu") && !el.is(":visible")) {
          var rule = current.parents("ul").first();
          var $targ = rule.find("ul:visible").slideUp(animationSpeed);
          $targ.removeClass("menu-open");
          var lis = current.parent("li");
          el.slideDown(animationSpeed, function() {
            el.addClass("menu-open");
            rule.find("li.active").removeClass("active");
            lis.addClass("active");
            a.layout.fix();
          });
        }
      }
      if (el.is(".treeview-menu")) {
        types.preventDefault();
      }
    });
  };
  $.NSdash.controlSidebar = {
    /**
     * @return {undefined}
     */
    activate : function() {
      var self = this;
      var data = $.NSdash.options.controlSidebarOptions;
      var win = $(data.selector);
      var cancel = $(data.toggleBtnSelector);
      cancel.on("click", function(types) {
        types.preventDefault();
        if (win.hasClass("control-sidebar-open") || $("body").hasClass("control-sidebar-open")) {
          self.close(win, data.slide);
        } else {
          self.open(win, data.slide);
        }
      });
      var tbody = $(".control-sidebar-bg");
      self._fix(tbody);
      if ($("body").hasClass("fixed")) {
        self._fixForFixed(win);
      } else {
        if ($(".content-wrapper, .right-side").height() < win.height()) {
          self._fixForContent(win);
        }
      }
    },
    /**
     * @param {Node} win
     * @param {?} opt_async
     * @return {undefined}
     */
    open : function(win, opt_async) {
      if (opt_async) {
        win.addClass("control-sidebar-open");
      } else {
        $("body").addClass("control-sidebar-open");
      }
    },
    /**
     * @param {HTMLElement} result
     * @param {?} code
     * @return {undefined}
     */
    close : function(result, code) {
      if (code) {
        result.removeClass("control-sidebar-open");
      } else {
        $("body").removeClass("control-sidebar-open");
      }
    },
    /**
     * @param {Object} wrapper
     * @return {undefined}
     */
    _fix : function(wrapper) {
      var instance = this;
      if ($("body").hasClass("layout-boxed")) {
        wrapper.css("position", "absolute");
        wrapper.height($(".wrapper").height());
        $(window).resize(function() {
          instance._fix(wrapper);
        });
      } else {
        wrapper.css({
          position : "fixed",
          height : "auto"
        });
      }
    },
    /**
     * @param {Object} $this
     * @return {undefined}
     */
    _fixForFixed : function($this) {
      $this.css({
        position : "fixed",
        "max-height" : "100%",
        overflow : "auto",
        "padding-bottom" : "50px"
      });
    },
    /**
     * @param {?} item
     * @return {undefined}
     */
    _fixForContent : function(item) {
      $(".content-wrapper, .right-side").css("min-height", item.height());
    }
  };
  $.NSdash.boxWidget = {
    selectors : $.NSdash.options.boxWidgetOptions.boxWidgetSelectors,
    icons : $.NSdash.options.boxWidgetOptions.boxWidgetIcons,
    animationSpeed : $.NSdash.options.animationSpeed,
    /**
     * @param {HTMLDocument} container
     * @return {undefined}
     */
    activate : function(container) {
      var self = this;
      if (!container) {
        /** @type {HTMLDocument} */
        container = document;
      }
      $(container).on("click", self.selectors.collapse, function(types) {
        types.preventDefault();
        self.collapse($(this));
      });
      $(container).on("click", self.selectors.remove, function(types) {
        types.preventDefault();
        self.remove($(this));
      });
    },
    /**
     * @param {Object} node
     * @return {undefined}
     */
    collapse : function(node) {
      var opts = this;
      var selectbox = node.parents(".box").first();
      var $el = selectbox.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
      if (selectbox.hasClass("collapsed-box")) {
        node.children(":first").removeClass(opts.icons.open).addClass(opts.icons.collapse);
        $el.slideDown(opts.animationSpeed, function() {
          selectbox.removeClass("collapsed-box");
        });
      } else {
        node.children(":first").removeClass(opts.icons.collapse).addClass(opts.icons.open);
        $el.slideUp(opts.animationSpeed, function() {
          selectbox.addClass("collapsed-box");
        });
      }
    },
    /**
     * @param {Object} keepData
     * @return {undefined}
     */
    remove : function(keepData) {
      var group_content = keepData.parents(".box").first();
      group_content.slideUp(this.animationSpeed);
    }
  };
}
if ("undefined" == typeof jQuery) {
  throw new Error("NSdash requires jQuery");
}
$.NSdash = {}, $.NSdash.options = {
  navbarMenuSlimscroll : true,
  navbarMenuSlimscrollWidth : "3px",
  navbarMenuHeight : "200px",
  animationSpeed : 500,
  sidebarToggleSelector : "[data-toggle='offcanvas']",
  sidebarPushMenu : true,
  sidebarSlimScroll : true,
  sidebarExpandOnHover : false,
  enableBoxRefresh : true,
  enableBSToppltip : true,
  BSTooltipSelector : "[data-toggle='tooltip']",
  enableFastclick : true,
  enableControlSidebar : true,
  controlSidebarOptions : {
    toggleBtnSelector : "[data-toggle='control-sidebar']",
    selector : ".control-sidebar",
    slide : true
  },
  enableBoxWidget : true,
  boxWidgetOptions : {
    boxWidgetIcons : {
      collapse : "fa-minus",
      open : "fa-plus",
      remove : "fa-times"
    },
    boxWidgetSelectors : {
      remove : '[data-widget="remove"]',
      collapse : '[data-widget="collapse"]'
    }
  },
  directChat : {
    enable : true,
    contactToggleSelector : '[data-widget="chat-pane-toggle"]'
  },
  colors : {
    lightBlue : "#3c8dbc",
    red : "#f56954",
    green : "#00a65a",
    aqua : "#00c0ef",
    yellow : "#f39c12",
    blue : "#0073b7",
    navy : "#001F3F",
    teal : "#39CCCC",
    olive : "#3D9970",
    lime : "#01FF70",
    orange : "#FF851B",
    fuchsia : "#F012BE",
    purple : "#8E24AA",
    maroon : "#D81B60",
    black : "#222222",
    gray : "#d2d6de"
  },
  screenSizes : {
    xs : 480,
    sm : 768,
    md : 992,
    lg : 1200
  }
}, $(function() {
  $("body").removeClass("hold-transition");
  if ("undefined" != typeof NSdashOptions) {
    $.extend(true, $.NSdash.options, NSdashOptions);
  }
  var options = $.NSdash.options;
  _init();
  $.NSdash.layout.activate();
  $.NSdash.tree(".sidebar");
  if (options.enableControlSidebar) {
    $.NSdash.controlSidebar.activate();
  }
  if (options.navbarMenuSlimscroll) {
    if ("undefined" != typeof $.fn.slimscroll) {
      $(".navbar .menu").slimscroll({
        height : options.navbarMenuHeight,
        alwaysVisible : false,
        size : options.navbarMenuSlimscrollWidth
      }).css("width", "100%");
    }
  }
  if (options.sidebarPushMenu) {
    $.NSdash.pushMenu.activate(options.sidebarToggleSelector);
  }
  if (options.enableBSToppltip) {
    $("body").tooltip({
      selector : options.BSTooltipSelector
    });
  }
  if (options.enableBoxWidget) {
    $.NSdash.boxWidget.activate();
  }
  if (options.enableFastclick) {
    if ("undefined" != typeof FastClick) {
      FastClick.attach(document.body);
    }
  }
  if (options.directChat.enable) {
    $(document).on("click", options.directChat.contactToggleSelector, function() {
      var $parent = $(this).parents(".direct-chat").first();
      $parent.toggleClass("direct-chat-contacts-open");
    });
  }
  $('.btn-group[data-toggle="btn-toggle"]').each(function() {
    var $e = $(this);
    $(this).find(".btn").on("click", function(types) {
      $e.find(".btn.active").removeClass("active");
      $(this).addClass("active");
      types.preventDefault();
    });
  });
}), function($) {
  /**
   * @param {?} object
   * @return {?}
   */
  $.fn.boxRefresh = function(object) {
    /**
     * @param {Object} node
     * @return {undefined}
     */
    function open(node) {
      node.append(q);
      obj.onLoadStart.call(node);
    }
    /**
     * @param {Object} node
     * @return {undefined}
     */
    function play(node) {
      node.find(q).remove();
      obj.onLoadDone.call(node);
    }
    var obj = $.extend({
      trigger : ".refresh-btn",
      source : "",
      /**
       * @param {?} evt
       * @return {?}
       */
      onLoadStart : function(evt) {
        return evt;
      },
      /**
       * @param {?} dataAndEvents
       * @return {?}
       */
      onLoadDone : function(dataAndEvents) {
        return dataAndEvents;
      }
    }, object);
    var q = $('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');
    return this.each(function() {
      if ("" === obj.source) {
        return void(window.console && window.console.log("Please specify a source first - boxRefresh()"));
      }
      var $this = $(this);
      var component = $this.find(obj.trigger).first();
      component.on("click", function(types) {
        types.preventDefault();
        open($this);
        $this.find(".box-body").load(obj.source, function() {
          play($this);
        });
      });
    });
  };
}(jQuery), function($) {
  /**
   * @return {undefined}
   */
  $.fn.activateBox = function() {
    $.NSdash.boxWidget.activate(this);
  };
}(jQuery), function($) {
  /**
   * @param {?} options
   * @return {?}
   */
  $.fn.todolist = function(options) {
    var settings = $.extend({
      /**
       * @param {?} event
       * @return {?}
       */
      onCheck : function(event) {
        return event;
      },
      /**
       * @param {?} dataAndEvents
       * @return {?}
       */
      onUncheck : function(dataAndEvents) {
        return dataAndEvents;
      }
    }, options);
    return this.each(function() {
      if ("undefined" != typeof $.fn.iCheck) {
        $("input", this).on("ifChecked", function() {
          var $this = $(this).parents("li").first();
          $this.toggleClass("done");
          settings.onCheck.call($this);
        });
        $("input", this).on("ifUnchecked", function() {
          var $this = $(this).parents("li").first();
          $this.toggleClass("done");
          settings.onUncheck.call($this);
        });
      } else {
        $("input", this).on("change", function() {
          var context = $(this).parents("li").first();
          context.toggleClass("done");
          if ($("input", context).is(":checked")) {
            settings.onCheck.call(context);
          } else {
            settings.onUncheck.call(context);
          }
        });
      }
    });
  };
}(jQuery);
