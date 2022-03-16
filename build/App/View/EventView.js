import Renderer from 'System/Renderer';

class EventView {
    _view(template, data)
    {
        Renderer.renderTemplateContent(template, data);
    }

    viewAbout(data)
    {
        this._view('app/event_about', data)
    }

    viewList(data)
    {
        this._view('app/event_list', data)
    }
}

export default new EventView();
