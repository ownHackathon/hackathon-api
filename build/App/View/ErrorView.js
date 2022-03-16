import Renderer from 'System/Renderer';

class ErrorView {
    view(template, data)
    {
        Renderer.renderTemplateContent(template, data);
    }
}

export default new ErrorView();
