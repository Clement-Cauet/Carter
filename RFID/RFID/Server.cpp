#include "Server.h"

Server::Server( QObject *parent ) : QObject( parent ) {

	// Webserver
	webServer = new QWebSocketServer( QStringLiteral( "WebServer" ), QWebSocketServer::NonSecureMode, this );

	QObject::connect( webServer, &QWebSocketServer::newConnection, this, &Server::onWebServerNewConnection );

	webServer->listen( QHostAddress::AnyIPv4, 4457 );

	reader = new Reader( );

	QTimer *timer = new QTimer(this);
	connect(timer, SIGNAL(timeout()), reader, SLOT(read()));
	connect(reader, SIGNAL(hasRead(QMap< QString, QVariant >)), this, SLOT( sendCardDetails(QMap< QString, QVariant >)));
	timer->start();

} 

void Server::onWebServerNewConnection() {

	QWebSocket * webClient = webServer->nextPendingConnection();
	QTcpSocket::connect( webClient, &QWebSocket::textMessageReceived, this, &Server::onWebClientCommunication );
	QTcpSocket::connect( webClient, &QWebSocket::disconnected, this, &Server::onWebClientDisconnected );
	
	( &this->etabishedConnection)->push_back( webClient );

}

void Server::onWebClientCommunication( QString entryMessage ) {

	QWebSocket * obj = qobject_cast<QWebSocket*>(sender());

	reader->write( entryMessage );
	
}

void Server::onWebClientDisconnected( ) {

	qDebug() << "Deco";
}


void Server::sendCardDetails( QMap< QString, QVariant > card ) {

	for ( QWebSocket *webSocket : this->etabishedConnection  ) {
		
		QString formatedTable = card[ "isset" ].toString() + "," + card[ "cardType" ].toString() + "," + card[ "cardSerial" ].toString() + "," + card["data"].toString();
		webSocket->sendTextMessage( formatedTable );
	}

}

Server::~Server() {
	qDebug() << "Destru";
}

