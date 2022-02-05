import Renderer from 'System/Renderer';

const loginHandler = {
    handle: () => {
        Renderer.renderTemplateContent('app/loginbox', {});
    },
}

export default loginHandler;
