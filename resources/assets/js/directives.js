// v-post on any anchor element to give the capability to post request to given route
Vue.directive('post', {
    bind: function (el, binding, vnode) {

      var id = Math.random().toString(36).substring(7);
      var route = binding.value;
      var attrib = `event.preventDefault();document.getElementById(\'${id}\').submit();`;

      if (binding.arg == 'confirm') {
        route = binding.value.route;
        attrib = `event.preventDefault();if(confirm('${binding.value.confirm_msg}')) {document.getElementById(\'${id}\').submit();}`
      }

      el.setAttribute('onclick', attrib);
      var form = `<form id="${id}" action="${route}" method="POST" style="display: none;">
      <input type="hidden" name="_token" value="${document.head.querySelector('meta[name="csrf-token"]').content}">
        </form>`
      el.innerHTML = el.textContent + form;
    }
})
