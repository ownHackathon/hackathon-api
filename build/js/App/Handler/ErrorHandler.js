import Renderer from 'System/Renderer';

const errorHandler = {
    handleNotFound: () => {
        return Renderer.renderTemplateContent('error/404', {});
    }
}

export default errorHandler;
