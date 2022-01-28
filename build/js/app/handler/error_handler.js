import renderer from 'system/renderer';

const error_handler = function () {
    let instance = {}
    instance.handle = function () {
        renderer.renderTemplateContent('error/404');
    }
    return instance;
}

export default error_handler();