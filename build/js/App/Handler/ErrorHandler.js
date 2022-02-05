import Renderer from 'System/Renderer';

const errorHandler = {
    handleNotFound: () => {
        Renderer.renderTemplateContent('error/404', {});
    },
}

export default errorHandler;
