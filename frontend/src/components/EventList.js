import React from 'react';
import { useQuery, gql } from '@apollo/client';

const GET_EVENTS = gql`
  query GetEvents {
    events {
      nodes {
        id
        title
        eventDate
        eventDescription
      }
    }
  }
`;

function EventList() {
  const { loading, error, data } = useQuery(GET_EVENTS);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  return (
    <ul>
      {data.events.nodes.map((event) => (
        <li key={event.id}>
          <h2>{event.title}</h2>
          <p>Date: {event.eventDate}</p>
          <p>Description: {event.eventDescription}</p>
        </li>
      ))}
    </ul>
  );
}

export default EventList;
