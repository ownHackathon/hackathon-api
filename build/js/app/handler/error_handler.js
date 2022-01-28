import renderer from './../../system/renderer';
import axios from './../../system/client';

const error_handler = function () {
    let instance = {}
    instance.handle = function () {
        axios
            .get('/template/error/404')
            .then(function (response) {
                const template_data = response.data;
                const rendered = renderer.render(template_data);
                document.getElementById('content').innerHTML = rendered;
            })
    }
    return instance;
}

export default error_handler();