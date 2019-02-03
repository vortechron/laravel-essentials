// v-post on any anchor element to give the capability to post request to given route
Vue.directive('post', {
    bind: function (el, binding, vnode) {
        var id = Math.random().toString(36).substring(7);
      el.setAttribute('onclick', `event.preventDefault();document.getElementById(\'${id}\').submit();`);
        
      var form = `<form id="${id}" action="${binding.value}" method="POST" style="display: none;">
      <input type="hidden" name="_token" value="${document.head.querySelector('meta[name="csrf-token"]').content}">
        </form>`
      el.innerHTML = el.textContent + form;
    }
})