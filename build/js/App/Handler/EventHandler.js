import Renderer from 'System/Renderer';
import EventService from 'App/Service/EventService';

const eventHandler = {
    handleAbout: () => {
        Renderer.renderTemplateContent('app/event_about', {});
    },

    handleList: () => {
        let data =  {
            'activeEvents': '1',
            'notActiveEvents': '2'
        }
        Renderer.renderTemplateContent('app/event_list', data)
    },
}

export default eventHandler;
