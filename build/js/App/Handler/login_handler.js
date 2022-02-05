import Renderer from '../../System/Renderer';

const login_handler = function () {
    let instance = {}
    instance.handle = function () {
        Renderer.renderTemplateContent('app/loginbox', {});
    }
    return instance;
}

export default login_handler();
