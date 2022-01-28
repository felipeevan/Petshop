import React from 'react';
import AppLayout from '@/layouts/AppLayout';

const Servico = (props) => {
  return (
    <div className="p-0 container-fluid">
      <div className="row">
        <div className="col-12">
          Servico
        </div>
      </div>
    </div>
  );
};

Servico.layout = page => <AppLayout children={page} />
export default Servico;