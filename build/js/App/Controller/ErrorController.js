import ErrorView from "App/View/ErrorView";

class ErrorController {
    handleNotFound()
    {
        ErrorView.view('error/404', {})
    }
}

export default new ErrorController();