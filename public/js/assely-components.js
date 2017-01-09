Vue.component('assely-alert', {
    template: '#tmpl-assely-alert'
});
Vue.component('assely-box', {
    template: '#tmpl-assely-box',

    props: [
        'title',
        'description',
        'type',
        'column'
    ]
});
Vue.component('assely-fields', {
    template: '#tmpl-assely-fields',

    props: [
        'fields',
        'namespace'
    ]
});
//# sourceMappingURL=assely-components.js.map
