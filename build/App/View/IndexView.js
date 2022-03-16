import Renderer from 'System/Renderer';

class IndexView {
    view(data)
    {
        Renderer.renderTemplateContent('app/index', data);
    }
}

export default new IndexView();
