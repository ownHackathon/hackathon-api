import renderer from './../../system/renderer';
import axios from './../../system/client';

const index_handler = function () {
    let instance = {}
    instance.handle = function () {
        axios
            .get('/template/index')
            .then(function (response) {
                const template_data = response.data;
                const rendered = renderer.render(template_data);
                document.getElementById('content').innerHTML = rendered;
            })
    }
    return instance;
}

export default index_handler();