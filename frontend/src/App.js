import React from 'react';
import { ApolloProvider } from '@apollo/client';
import client from './apolloClient';
import EventList from './components/EventList';

function App() {
  return (
    <ApolloProvider client={client}>
      <div className="App">
        <h1>Events</h1>
        <EventList />
      </div>
    </ApolloProvider>
  );
}

export default App;
