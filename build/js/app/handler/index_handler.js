import renderer from 'system/renderer';

const index_handler = function () {
    let instance = {}
    instance.handle = function () {
        renderer.renderTemplateContent('app/index');
    }
    return instance;
}

export default index_handler();