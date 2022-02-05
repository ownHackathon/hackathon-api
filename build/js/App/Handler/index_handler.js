import Renderer from '../../System/Renderer';

const index_handler = function () {
    let instance = {}
    instance.handle = function () {
        Renderer.renderTemplateContent('app/index', {});
    }
    return instance;
}

export default index_handler();
