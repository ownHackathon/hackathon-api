import Mustache from "mustache";
import Axios from 'System/Client';

const renderer = {
    renderTemplateContent: async function (template, data) {
        this.setContentById(await this.render(template, data), 'content');
    },

    setContentById: (content, id) => {
        document.getElementById(id).innerHTML = content;
    },

    render: (template, data) => {
        return Axios
            .get('/template/' + template)
            .then((response) => {
                const templateContent = response.data;
                return Mustache.render(templateContent, data);
            })
    }
};

export default renderer;
