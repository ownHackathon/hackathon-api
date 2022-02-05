import Renderer from 'System/Renderer';
import Axios from "axios";

const eventHandler = {
    handleAbout: () => {
        Renderer.renderTemplateContent('app/event_about', {});
    },

    handleList: () => {
        Axios
            .get('/event/list')
            .then(async function (response) {
                const activeEvent = await Renderer.render('/partial/event_list_event', {})
            })

        Renderer.renderTemplateContent('app/event_list', {})
    },
}

export default eventHandler;
