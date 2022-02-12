import Renderer from 'System/Renderer';

class EventView {
    view(template, data)
    {
        Renderer.renderTemplateContent(template, data);
    }

    viewAbout(data)
    {
        this.view('app/event_about', data)
    }

    viewList(data)
    {
        this.view('app/event_list', data)
    }
}

export default new EventView();