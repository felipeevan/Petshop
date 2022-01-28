import AppLayout from '@/layouts/AppLayout';
import React, { useState } from 'react';

import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Container,
    Modal,
    ModalHeader,
    ModalBody,
    Form,
    FormGroup,
    Input,
    Label,
    Row,
    Col,
    DropdownItem,
    DropdownMenu,
    DropdownToggle,
    UncontrolledDropdown,
    Badge,
    Dropdown,
    ModalFooter
} from "reactstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus, faTrash, faPen, faEllipsisV, faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import ToolkitProvider from 'react-bootstrap-table2-toolkit/dist/react-bootstrap-table2-toolkit.min';
import BootstrapTable from "react-bootstrap-table-next";
import paginationFactory from "react-bootstrap-table2-paginator";
import { useForm } from 'react-hook-form';
import InputMask from "react-input-mask";

const Funcionario = (props) => {
    const [dadosTables, setDadosTable] = useState([]);
    const [dadosModal, setDadosTabela] = useState({
        idTabela: null,
        tabelaEdit: [],
        tipo: 0
    })
    const [modal, setModal] = useState(false);
    const toggleModal = () => setModal(!modal);

    const handleIncluir = () => {
        setDadosTabela({
            idTabela: null,
            tabelaEdit: [],
            tipo: 0,
        })
        toggleModal()
    }

    const {
        register,
        handleSubmit,
        errors,
        control
    } = useForm();

    const tableColumns = [
        {
            dataField: "cpf",
            text: "CPF",
            sort: true
        },
        {
            dataField: "celular",
            text: "Celular",
            sort: true
        },
        {
            dataField: "email",
            text: "Email",
            sort: true
        },
        {
            dataField: "action",
            text: "Ações",
            isDummyField: true,
            formatter: (cell, row, rowIndex) => {
                return (
                    <div></div>
                );
            },
            csvExport: false
        }
    ];

    const submit = async (data) => {
        if(tipoModal==0){
            axios.post(route('clientes.processos.create'), {
                data
            }).then((response) =>  {
                
            }).catch((error)=>{
                
            });
        }
    }

    return (
        <div>
            <Container fluid className="p-0">
                <div>
                    <Button color="primary" className="float-end ms-1" onClick={handleIncluir}>
                        <FontAwesomeIcon icon={faPlus} /> Adicionar
                    </Button>

                    <h1 className="h3 mb-3">Funcionários</h1>

                </div>

                <ToolkitProvider
                    keyField="id"
                    data={dadosTables}
                    columns={tableColumns}
                >
                    {
                        props => (
                            <Card>
                                <CardBody>
                                    <BootstrapTable
                                        {...props.baseProps}
                                        bootstrap4
                                        condensed
                                        bordered={true}
                                        pagination={paginationFactory({
                                            sizePerPage: 5,
                                            sizePerPageList: [5, 10, 25, 50]
                                        })}
                                    />
                                </CardBody>
                            </Card>
                        )
                    }
                </ToolkitProvider>
            </Container>
            <Modal isOpen={modal} toggle={toggleModal} className={"classe"} centered={false} size="lg"
                style={{ maxWidth: 'none', width: '50%', "height": "100%", "margin": "0 auto", "padding": "20px 0" }} contentClassName="modalContent">
                <ModalHeader toggle={toggleModal}>{dadosModal.tipo == 0 ? "Novo Funcionário" : "Editar Funcionário"}</ModalHeader>
                <ModalBody style={{ backgroundColor: '#F5F9FC', maxHeight: '100%', overflowY: 'auto' }}>
                    <Card>
                        <CardBody>
                            <Form onSubmit={handleSubmit(submit)} encType="multipart/form-data"
                                id="form" name="form">
                                <Row>
                                    <Col md="12">
                                        <FormGroup>
                                            <Label for="cpf">CPF</Label>
                                            <Input 
                                            type="text" 
                                            id="cpf" 
                                            name="cpf" 
                                            mask="999.999.999-99" 
                                            maskChar=" "
                                            tag={InputMask}
                                            inputRef={register({ required: true})} 
                                            invalid={errors["cpf"]!=null}
                                            />
                                        </FormGroup>
                                    </Col>
                                    <Col md="12">
                                        <FormGroup>
                                            <Label for="celular">Celular</Label>
                                            <Input 
                                            type="text" 
                                            id="celular" 
                                            name="celular" 
                                            mask="(99) 99999-9999" 
                                            maskChar=" "
                                            tag={InputMask}
                                            inputRef={register({ required: true})} 
                                            invalid={errors["celular"]!=null}
                                            />
                                        </FormGroup>
                                    </Col>
                                    <Col md="12">
                                        <FormGroup>
                                            <Label for="email">Email</Label>
                                            <Input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            inputRef={register({ required: true})} 
                                            invalid={errors["email"]!=null}
                                            />
                                        </FormGroup>
                                    </Col>
                                </Row>
                                <ModalFooter>
                                    <Button color="primary" type="submit">Salvar</Button>
                                </ModalFooter>
                            </Form>
                        </CardBody>
                    </Card>
                </ModalBody>
            </Modal>
        </div>
    );
};

Funcionario.layout = page => <AppLayout children={page} />
export default Funcionario;