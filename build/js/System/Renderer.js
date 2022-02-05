import Mustache from "mustache";
import Axios from './Client';

const renderer = () => {
    let instance = {};

    instance.renderTemplateContent = async function (template, data) {
        this.setContentById(await this.render(template, data), 'content');
    }

    instance.setContentById = (content, id) => {
        document.getElementById(id).innerHTML = content;
    }

    instance.render = (template, data) => {
        return Axios
            .get('/template/' + template)
            .then((response) => {
                const templateContent = response.data;
                return Mustache.render(templateContent, data);
            })
    }

    return instance;
};

export default renderer();