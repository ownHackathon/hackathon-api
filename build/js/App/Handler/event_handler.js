import Renderer from '../../System/Renderer';
import Axios from "axios";

const event_handler = () => {
    let instance = {}

    instance.about = () => {
        Renderer.renderTemplateContent('app/event_about', {});
    }

    instance.list = () => {
        Axios
            .get('/event/list')
            .then(async function (response) {
                const activeEvent = await Renderer.render('/partial/event_list_event', {})
            })

        Renderer.renderTemplateContent('app/event_list', {})
    }

    return instance;
}

export default event_handler();
