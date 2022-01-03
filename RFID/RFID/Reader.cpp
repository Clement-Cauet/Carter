#include "Reader.h"

Reader::Reader( QObject *parent ) : QObject( parent ) {

	qDebug() << "Reader :: start";

	int status = ReaderOpen();

	GetReaderType( &readerType );
	ReaderUISignal( 3, 3 );

}

void Reader::read( ) {	

	QMap< QString, QVariant > Card;

	Card["isset"] = false;

	if ( GetDlogicCardType( &cardType ) )	
		return emit hasRead( Card );

	GetCardId( &cardType, &cardSerial );

	Card[ "cardType"	] = cardType;
	Card[ "cardSerial"	] = cardSerial;

	if ( cardType == DL_MIFARE_CLASSIC_1K )
		return emit hasRead( Card );

	Card[ "isset" ] = true;

	unsigned char	ucKeyIndex		= 0,
					ucAuthMode		= MIFARE_AUTHENT1A;

	unsigned short	usLinearAddress = 0,
					usDataLength	= 20,
					usBytesRet		= 0;

	unsigned char  *pData			= 0;

	pData = new unsigned char[usDataLength];
	LinearRead( pData, usLinearAddress, usDataLength, &usBytesRet, ucAuthMode, ucKeyIndex );


	Card["data"] = ( char* )pData;

	emit hasRead( Card );

	cardType	= NULL;
	cardSerial	= NULL;
	Card.clear();

}

void Reader::write( QString newContent ) {

	unsigned char	ucKeyIndex			= 0,
					ucAuthMode			= MIFARE_AUTHENT1A;

	unsigned short	usLinearAddress		= 0,
					usDataLength		= 10,
					usBytesRet			= 10;

	QByteArray test = newContent.toLatin1();
	
	unsigned char *res = (unsigned char *)strdup(test.constData());


	LinearWrite(res, usLinearAddress, usDataLength, &usBytesRet, ucAuthMode, ucKeyIndex );
	Reader::read();
}

Reader::~Reader() {


}
