pipeline {
  agent any
  environment {
    BUILD_HOME='/var/lib/jenkins/workspace/aichi-kousoku-app-src'
	BUILD_PSRC='/localsrc/php81/aichi-kousoku-app-src/'
	BUILD_PSRC_2='/localsrc/php81/kousoku-app-src/'
  }
  stages {
    stage('Sync src to localsrc') {
		steps {
			sh "rsync -avz $BUILD_HOME/ $BUILD_PSRC --delete --exclude '.git' --exclude '.env' --exclude '.gitignore' --exclude 'storage' --exclude 'bootstrap/cache' --exclude 'vendor'" 
			sh "rsync -avz $BUILD_HOME/ $BUILD_PSRC_2 --delete --exclude '.git' --exclude '.env' --exclude '.gitignore' --exclude 'storage' --exclude 'bootstrap/cache' --exclude 'vendor'" 
			sh "sudo chmod -R 777 /localsrc/php81/aichi-kousoku-app-src/storage"
			sh "sudo chmod -R 777 /localsrc/php81/kousoku-app-src/storage"
		}
    }
  }
  post {
	success {
            slackSend channel: "#aichi-kousoku-unyu", color: 'good', message: 'Source has been synced Successfully!!!!!'
			echo 'I was successful!'
    }
	failure {
            slackSend channel: "#aichi-kousoku-unyu", color: '#FF0000', message: 'Source has been synced Failed!!!'
			echo 'I failed :('
    }
  }
}