#include "Server.h"

Server::Server( QObject *parent ) : QObject( parent ) {

	webServer = new QWebSocketServer( QStringLiteral( "WebServer" ), QWebSocketServer::NonSecureMode, this );

    // Open configuration file for reading port number
    QFile file( "config.ini" );
    if ( !file.open( QIODevice::ReadOnly ) ) {
        qDebug() << "Error, can't open config.ini";
        exit( -1 );
    }

    QTextStream in( &file );
    QString line		= in.readLine();
    QStringList list	= line.split( "=" );
    QString port		= list.at( 1 );

    if ( !webServer->listen( QHostAddress::Any, port.toInt() ) ) {
        qDebug() << "Error, can't listen on port " << port;
        exit( -1 );
    }

	QObject::connect( webServer, &QWebSocketServer::newConnection, this, &Server::onWebServerNewConnection );

	reader = new Reader( );

	QTimer *timer = new QTimer( this );
	connect( timer, SIGNAL( timeout() ), reader, SLOT( read() ) );
	connect( reader, SIGNAL( hasRead( QMap< QString, QVariant > ) ), this, SLOT( sendCardDetails( QMap< QString, QVariant > ) ) );
	timer->start();

}

void Server::onWebServerNewConnection() {

    QWebSocket *webSocket = webServer->nextPendingConnection();

	QTcpSocket::connect( webSocket, &QWebSocket::textMessageReceived, this, &Server::onWebClientCommunication );
	QTcpSocket::connect( webSocket, &QWebSocket::disconnected, this, &Server::onWebClientDisconnected );

	( &this->etabishedConnection )->push_back( webSocket );
    qDebug() << "Nouvelle connexion de " << webSocket->peerAddress().toString();

}

void Server::onWebClientCommunication( QString entryMessage ) {

	QWebSocket * obj = qobject_cast< QWebSocket *>( sender() );

    qDebug() << "Demande de modification de la carte : " << entryMessage;

	reader->write( entryMessage );

}

void Server::onWebClientDisconnected( ) {

	qDebug() << "Client deconnecte";

}


void Server::sendCardDetails( QMap< QString, QVariant > card ) {

	for ( QWebSocket *webSocket : this->etabishedConnection  ) {

		QString formatedTable = card[ "isset" ].toString() + "," + card[ "cardType" ].toString() + "," + card[ "cardSerial" ].toString() + "," + card["data"].toString();
		webSocket->sendTextMessage( formatedTable );

	}

}

Server::~Server() {

	qDebug() << "Destruction du serveur";

}