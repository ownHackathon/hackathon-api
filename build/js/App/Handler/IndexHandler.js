import Renderer from 'System/Renderer';

const indexHandler = {
    handle: () => {
        Renderer.renderTemplateContent('app/index', {});
    },
}

export default indexHandler;
