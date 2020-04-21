import TreeView from "vue-json-tree-view"

Nova.booting((Vue, router, store) => {
    Vue.use(TreeView);
    Vue.component('api-inspector', require('./components/Tool'))
})
