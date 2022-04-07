import moment from "moment";

function dateTime(value)
{
    return moment(value).format("DD.MM.YYYY - HH:mm U\\hr");
}

function date(value)
{
    return moment(value).format("DD.MM.YYYY");
}

function addTime(value, count)
{
    return moment(value, "DD-MM-YYYY hh:mm").add(count, 'days');
}

export {
    date,
    dateTime,
    addTime,
};
