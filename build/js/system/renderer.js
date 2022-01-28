import Mustache from "mustache";
import axios from 'system/client';

const renderer = function () {
    let instance = {};

    instance.renderTemplateContent = async function (template, data) {
        this.setContentById(await this.render(template, data), 'content');
    }

    instance.setContentById = function (content, id) {
        document.getElementById(id).innerHTML = content;
    }

    instance.render = function (template, data) {
        return axios
            .get('/template/' + template)
            .then(function (response) {
                const templateContent = response.data;
                return Mustache.render(templateContent, data);
            })
    }

    return instance;
};

export default renderer();