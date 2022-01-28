import renderer from 'system/renderer';

const login_handler = function () {
    let instance = {}
    instance.handle = function () {
        renderer.renderTemplateContent('app/loginbox', {  });
    }
    return instance;
}

export default login_handler();