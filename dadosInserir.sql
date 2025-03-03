-- Inserindo dados na tabela produto_tabela
INSERT INTO produto_tabela (preco, descricao) VALUES
(10.99, 'Produto A'),
(20.50, 'Produto B'),
(15.30, 'Produto C'),
(40.75, 'Produto D'),
(5.99, 'Produto E'),
(60.00, 'Produto F'),
(70.45, 'Produto G'),
(25.90, 'Produto H'),
(12.00, 'Produto I'),
(30.80, 'Produto J');

-- Inserindo dados na tabela cliente_tabela
INSERT INTO cliente_tabela (nome, cpf_cnpj, razao_social) VALUES
('Cliente 1', '123.456.789-00', 'Empresa A'),
('Cliente 2', '987.654.321-00', 'Empresa B'),
('Cliente 3', '321.654.987-00', 'Empresa C'),
('Cliente 4', '456.789.123-00', 'Empresa D'),
('Cliente 5', '789.123.456-00', 'Empresa E'),
('Cliente 6', '159.753.456-00', 'Empresa F'),
('Cliente 7', '357.951.852-00', 'Empresa G'),
('Cliente 8', '654.852.159-00', 'Empresa H'),
('Cliente 9', '789.654.321-00', 'Empresa I'),
('Cliente 10', '852.753.951-00', 'Empresa J');

-- Inserindo dados na tabela pedido_tabela
INSERT INTO pedido_tabela (produto_id, cliente_id, status, quantidade, data_pedido) VALUES
(1, 1, 'Em Aberto', 5, NOW()),
(2, 2, 'Pago', 2, NOW()),
(3, 3, 'Cancelado', 1, NOW()),
(4, 4, 'Em Aberto', 3, NOW()),
(5, 5, 'Pago', 4, NOW()),
(6, 6, 'Cancelado', 2, NOW()),
(7, 7, 'Em Aberto', 1, NOW()),
(8, 8, 'Pago', 3, NOW()),
(9, 9, 'Cancelado', 5, NOW()),
(10, 10, 'Em Aberto', 2, NOW());
