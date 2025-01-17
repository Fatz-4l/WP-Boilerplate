export default class BaseTemplate {
    constructor() {
        this.$el = document.querySelector('selector');
        if (!this.$el) return;
        this.init();
    }

    init() {

    }
}