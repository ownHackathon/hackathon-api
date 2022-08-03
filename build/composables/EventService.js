export default function useEventService() {
  function hasParticipants(participantsList)  {
    return (participantsList !== undefined && participantsList.length > 0);
  }

  function canStillParticipate(eventStatus) {
    return eventStatus < 3;
  }

  function findUserInParticipantList(participantList, user){
    if (hasParticipants(participantList) && user !== null) {
      return participantList.find(element => element.username === user.name);
    }

    return null;
  }

  function isUserInParticipantList(participantList, user)  {
    return !!findUserInParticipantList(participantList, user);
  }

  return {
    canStillParticipate, hasParticipants, findUserInParticipantList, isUserInParticipantList
  };
}
