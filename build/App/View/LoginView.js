import Renderer from 'System/Renderer';

class LoginView {
    view(data)
    {
        Renderer.renderTemplateContent('app/loginbox', data);
    }
}

export default new LoginView();
