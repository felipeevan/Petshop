import React from 'react';
import AppLayout from '@/layouts/AppLayout';

const Atendimento = (props) => {
  return (
    <div className="p-0 container-fluid">
      <div className="row">
        <div className="col-12">
          Atendimento
        </div>
      </div>
    </div>
  );
};

Atendimento.layout = page => <AppLayout children={page} />
export default Atendimento;