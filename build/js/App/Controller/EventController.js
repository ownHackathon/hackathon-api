import EventView from "App/View/EventView";

class EventController {
    about()
    {
        EventView.viewAbout({})
    }

    list()
    {
        let data =  {
            'activeEvents': '1',
            'notActiveEvents': '2'
        }

        EventView.viewList(data)
    }
}

export default new EventController();