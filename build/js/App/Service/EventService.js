import Axios from "axios";
import Renderer from "System/Renderer";

const eventService = {
    getEventList: () => {
        Axios
            .get('/event/list')
            .then(async function (response) {
                const activeEvent = await Renderer.render('/partial/event_list_event', {})
            })
        return {}
    }
};

export default eventService;
