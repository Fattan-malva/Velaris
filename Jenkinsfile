pipeline {
    agent any
    environment {
        DEPLOY_USER = 'root'
        DEPLOY_HOST = '104.43.65.128'
    }
    stages {
        stage('Change Directory and Pull Latest Code') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'GSIDEV', usernameVariable: 'USER', passwordVariable: 'PASS')]) {
                    sh """
                    sshpass -p '$PASS' ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no $DEPLOY_USER@$DEPLOY_HOST '
                        git config --global --add safe.directory /data/assetmanagement/Management_Assets &&
                        cd /data/assetmanagement/Management_Assets &&
                        git pull origin master'
                    """
                }
            }
        }
        stage('Restart Laravel Service') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'GSIDEV', usernameVariable: 'USER', passwordVariable: 'PASS')]) {
                    sh """
                    sshpass -p '$PASS' ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no $DEPLOY_USER@$DEPLOY_HOST '
                        cd /data/assetmanagement/Management_Assets &&
                        php artisan queue:restart'
                    """
                }
            }
        }
    }
}
