import Renderer from '../../System/Renderer';

const errorHandler = () => {
    let instance = {}
    instance.handle = () => {
        Renderer.renderTemplateContent('error/404', {});
    }
    return instance;
}

export default errorHandler();
