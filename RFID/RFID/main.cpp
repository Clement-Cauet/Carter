#include <QtCore/QCoreApplication>
#include <qdebug.h>
#include "./Server.h"


int main( int argc, char *argv[] ) {

	QCoreApplication a( argc, argv );

	Server *server = new Server;

	return a.exec();

}
