import Renderer from 'System/Renderer';

class IndexController {
    handle()
    {
        Renderer.renderTemplateContent('app/index', {});
    }
}

export default new IndexController();