import moment from "moment";

function dateTime(value)
{
    return moment(value).format("DD.MM.YYYY - hh:mm U\\hr");
}

function date(value)
{
    return moment(value).format("DD.MM.YYYY");
}

export {
    date,
    dateTime,
};
