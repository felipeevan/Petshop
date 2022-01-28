import React from 'react';
import AppLayout from '@/layouts/AppLayout';

const Home = (props) => {
  return (
    <div className="p-0 container-fluid">
      <div className="row">
        <div className="col-12">
          Home
        </div>
      </div>
    </div>
  );
};

Home.layout = page => <AppLayout children={page} />
export default Home;