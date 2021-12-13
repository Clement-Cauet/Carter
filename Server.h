#pragma once

#include <QObject>
#include <QWebSocket>
#include <QWebSocketServer>
#include <QTimer>
#include "Reader.h"

QT_FORWARD_DECLARE_CLASS( QWebSocketServer )
QT_FORWARD_DECLARE_CLASS( QWebSocket )

class Reader;
class Server : public QObject {
	
	Q_OBJECT

	public:
		Server( QObject *parent = Q_NULLPTR );
		~Server();

	private:
		QWebSocketServer *webServer;
		Reader *reader;

		std::vector< QWebSocket * > etabishedConnection;

	public slots:
		void onWebServerNewConnection( );
		void onWebClientDisconnected( );
		void onWebClientCommunication( QString entryMessage );

		void sendCardDetails( QMap< QString, QVariant > );
};
