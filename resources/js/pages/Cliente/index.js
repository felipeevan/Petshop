import React from 'react';
import AppLayout from '@/layouts/AppLayout';

const Cliente = (props) => {
  return (
    <div className="p-0 container-fluid">
      <div className="row">
        <div className="col-12">
          Cliente
        </div>
      </div>
    </div>
  );
};

Cliente.layout = page => <AppLayout children={page} />
export default Cliente;